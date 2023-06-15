export type Spaces = {
  name: string;
  dimensions: {
    width: number;
    height: number;
  };
  desks: {
    name: string;
    position: {
      x: number;
      y: number;
      angle: number;
    };
  }[];
};
