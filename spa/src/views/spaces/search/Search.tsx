import { Autocomplete } from "@mui/material";
import { SearchProps } from "../../../interfaces/props/SearchProps";
import TextField from "@mui/material/TextField";
import classes from "./Search.module.scss";

const Search = ({ searchTerm, onSearchTermChange, options }: SearchProps) => {
  return (
    <Autocomplete
      classes={{
        root: classes["search-bar"],
        endAdornment: classes["end-andorment"],
      }}
      freeSolo
      disablePortal
      clearOnEscape
      options={options}
      value={searchTerm ?? ""}
      size={"small"}
      isOptionEqualToValue={(option, value) => option === value}
      renderInput={(params) => (
        <TextField
          {...params}
          label="Spaces"
          InputProps={{
            ...params.InputProps,
          }}
        />
      )}
      onInputChange={(event, newInputValue) => {
        onSearchTermChange(newInputValue ?? "");
      }}
    />
  );
};

export default Search;
