import Snackbar from "@mui/material/Snackbar";
import { NotificationProps } from "../../interfaces/props/NotificationProps";
import Alert from "@mui/material/Alert";

const Notification = ({
  severity,
  message,
  open,
  closeHandler,
}: NotificationProps) => {
  return (
    <Snackbar open={open} autoHideDuration={4000} onClose={closeHandler}>
      <Alert onClose={closeHandler} severity={severity} sx={{ width: "100%" }}>
        {message}
      </Alert>
    </Snackbar>
  );
};

export default Notification;
