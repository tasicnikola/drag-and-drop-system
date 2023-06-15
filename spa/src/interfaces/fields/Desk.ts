import { CreatedAt } from "./CreatedAt";
import { UpdatedAt } from "./UpdatedAt";
import { Space } from "../Space";

export interface Desk {
  guid: string;
  name: string;
  position: {
    x: number;
    y: number;
    angle: number;
  };
  space: Space;
  createdAt: CreatedAt;
  updatedAt: UpdatedAt | null;
}
