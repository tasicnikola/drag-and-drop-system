export interface NotificationProps {
  severity: "success" | "error";
  message: string;
  open: boolean;
  closeHandler: () => void;
}
