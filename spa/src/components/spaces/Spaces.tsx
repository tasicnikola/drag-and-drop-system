import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import TableCell from "@mui/material/TableCell";
import TableContainer from "@mui/material/TableContainer";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import {
  Box,
  Button,
} from "@mui/material";
import { ChangeEvent, MouseEvent, useEffect, useState } from "react";
import TablePagination from "@mui/material/TablePagination";
import Search from "../../views/spaces/search/Search";
import classes from "./Spaces.module.scss";
import DeleteIcon from "@mui/icons-material/DeleteOutlined";
import { Link } from "react-router-dom";
import SpaceService from "../../services/space.service";
import { Space as SpaceModel } from "../../interfaces/Space";
import Ghost from "../../views/ghost/Ghost";
import DeleteDialog from "../../views/spaces/delete-dialog/DeleteDialog";

const Spaces = () => {
  const [page, setPage] = useState(0);
  const [rowsPerPage, setRowsPerPage] = useState(16);
  const [searchTerm, setSearchTerm] = useState<string>("");
  const [isLoading, setIsLoading] = useState(false);
  const [spaces, setSpaces] = useState<SpaceModel[]>([]);
  const [initialSpaces, setInitialSpaces] = useState<SpaceModel[]>([]);
  const [filteredSpaces, setFilteredSpaces] = useState<SpaceModel[]>([]);
  const [selectedSpace, setSelectedSpace] = useState<SpaceModel | null>(null);
  const [dialogOpen, setDialogOpen] = useState<boolean>(false);

  useEffect(() => {
    setIsLoading(true);
    SpaceService.getAll()
      .then((data: SpaceModel[]) => {
        setSpaces(data);
        setInitialSpaces(data);
      })

      .catch((err) => console.log(err))
      .finally(() => setIsLoading(false));
  }, []);

  const filteredData = spaces.filter(
    (space: SpaceModel) =>
    space.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      searchTerm === ""
  );

  const handleChangePage = (
    event: MouseEvent<HTMLButtonElement> | null,
    newPage: number
  ) => setPage(newPage);

  const handleChangeRowsPerPage = (event: ChangeEvent<HTMLInputElement>) => {
    setRowsPerPage(parseInt(event.target.value, 10));
    setPage(0);
  };

  const filterSpaces = (spaces: SpaceModel[], searchTerm: string) => {
    return spaces
      .filter((space: SpaceModel) =>
        space.name.toLowerCase().includes(searchTerm.toLowerCase())
      )
      .map((space: SpaceModel) => space.name);
  };

  const handleDeleteClick = (space: SpaceModel) => {
    setSelectedSpace(space);
    setDialogOpen(true);
  };

  const handleDialogClose = (): void => {
    setSelectedSpace(null);
    setDialogOpen(false);
  };

  const handleDeleteConfirm = (): void => {
    if (!selectedSpace) {
      return;
    }

    SpaceService.delete(selectedSpace.guid)
      .then(() => {
        const indexToRemove = filteredSpaces.findIndex(
          (space) => space.guid === selectedSpace.guid
        );
        const updatedFilteredSpaces = [
          ...filteredSpaces.slice(0, indexToRemove),
          ...filteredSpaces.slice(indexToRemove + 1),
        ];

        setFilteredSpaces(updatedFilteredSpaces);

        const updatedSpaces = spaces.filter(
          (space) => space.guid !== selectedSpace.guid
        );

        setSpaces(updatedSpaces);
        setSelectedSpace(null);
        setDialogOpen(false);

        const initialSpacesCopy = [...initialSpaces];
        const indexToRemoveInitial = initialSpacesCopy.findIndex(
          (space) => space.guid === selectedSpace.guid
        );
        const updatedInitialSpaces = [
          ...initialSpacesCopy.slice(0, indexToRemoveInitial),
          ...initialSpacesCopy.slice(indexToRemoveInitial + 1),
        ];

        setInitialSpaces(updatedInitialSpaces);
      })
      .catch((err) => console.log(err));
  };

  return (
    <Box className="m-3">
      <Box className={"mb-3 d-flex justify-content-between"}>
        <Search
          searchTerm={searchTerm}
          onSearchTermChange={(value: string) => setSearchTerm(value)}
          options={filterSpaces(spaces, searchTerm)}
        />
      </Box>
      <Box className={"boxed"}>
        {isLoading ? (
          <Ghost className="spaces" />
        ) : (
          <div>
            <TableContainer>
              <Table aria-label="simple table">
                <TableHead>
                  <TableRow>
                    <TableCell>Spaces</TableCell>
                    <TableCell className="pr-2" align="right"></TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {(rowsPerPage > 0
                    ? filteredData.slice(
                        page * rowsPerPage,
                        page * rowsPerPage + rowsPerPage
                      )
                    : filteredData
                  ).map((space) => (
                    <TableRow
                      key={space.guid}
                      className={"last-child-no-border"}
                    >
                      <TableCell>
                        <Link
                          to={`/space/${space.guid}`}
                          className={`text-decoration-none text-link ${classes["space-name"]}`}
                        >
                          {space.name}
                        </Link>
                      </TableCell>
                      <TableCell align="right">
                        <Button
                          onClick={() => handleDeleteClick(space)}
                          classes={{ root: classes.button }}
                        >
                          <DeleteIcon />
                        </Button>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </TableContainer>
            <Box className={classes["table-pagination"]}>
              <TablePagination
                classes={{
                  root: `${classes.root} d-flex justify-content-end align-items-center`,
                  toolbar: `${classes.toolbar} d-flex align-items-center`,
                  select: classes.select,
                  actions: `me-0 d-flex`,
                  spacer: "flex-1",
                  displayedRows: classes["displayed-rows"],
                  selectLabel: classes["selected-label"],
                }}
                rowsPerPageOptions={[8, 16, 24, 48]}
                component="div"
                count={spaces.length}
                rowsPerPage={rowsPerPage}
                page={page}
                onPageChange={handleChangePage}
                onRowsPerPageChange={handleChangeRowsPerPage}
              />
              <DeleteDialog
                open={dialogOpen}
                onClose={handleDialogClose}
                onConfirm={handleDeleteConfirm}
              />
            </Box>
          </div>
        )}
      </Box>
    </Box>
  );
};

export default Spaces;
