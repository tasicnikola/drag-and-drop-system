import { Skeleton } from "@mui/material";
import GhostProps from "../../interfaces/props/GhostProps";

const Ghost = ({ className }: GhostProps) => {
  const defaultClassName = "ghost";
  const ghostClassName = className
    ? `${className} ${defaultClassName}`
    : `${defaultClassName}`;

  return (
    <Skeleton
      variant="rounded"
      width="100%"
      classes={{ root: ghostClassName }}
    />
  );
};

export default Ghost;
