import { Guid } from "./fields/Guid";
import { Name } from "./fields/Name";
import { CreatedAt } from "./fields/CreatedAt";
import { UpdatedAt } from "./fields/UpdatedAt";
import { Desk } from "./fields/Desk";

export interface Space extends Guid, Name, CreatedAt, UpdatedAt {
  dimension: {
    height: number;
    width: number;
  };
  desks: Desk[];
}
