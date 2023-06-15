import {
  Dialog,
  DialogTitle,
  DialogContent,
  DialogContentText,
  DialogActions,
  Button,
} from "@mui/material";
import { DeleteSpaceProps } from "../../../interfaces/props/DeleteSpaceProps";
import classes from "./DeleteDialog.module.scss";

const DeleteDialog = ({ open, onClose, onConfirm }: DeleteSpaceProps) => {
  return (
    <Dialog open={open} onClose={onClose}>
      <DialogTitle>Delete Space</DialogTitle>
      <DialogContent>
        <DialogContentText>
          Are you sure you want to delete this space?
        </DialogContentText>
      </DialogContent>
      <DialogActions>
        <Button
          onClick={onClose}
          classes={{ root: `${classes["cancel-button"]}` }}
        >
          Cancel
        </Button>
        <Button
          onClick={onConfirm}
          variant="contained"
          classes={{ root: `${classes["delete-button"]}` }}
        >
          Delete
        </Button>
      </DialogActions>
    </Dialog>
  );
};

export default DeleteDialog;
