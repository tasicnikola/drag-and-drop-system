import DBSService from "./dnd.service";
import { Space as SpaceModel } from "../interfaces/Space";
import { Spaces as SpacesParams } from "../interfaces/Spaces";

class SpaceService {
  public static getAll(): Promise<SpaceModel[]> {
    return DBSService.sendRequest<SpaceModel[]>("GET", "spaces")
      .then((data: SpaceModel[]) => data)
      .catch((err) => {
        console.log(err);
        throw err;
      });
  }

  public static getByID(guid: string): Promise<SpaceModel> {
    return DBSService.sendRequest<SpaceModel>("GET", `spaces/${guid}`)
      .then((data: SpaceModel) => data)
      .catch((err) => {
        console.log(err);
        throw err;
      });
  }

  public static delete(guid: string): Promise<void> {
    return DBSService.sendRequest<void>("DELETE", `spaces/${guid}`)
      .then((data: void) => {})
      .catch((err) => console.log(err));
  }

  public static create(parameters: SpacesParams): Promise<string> {
    return DBSService.sendRequest<any>("POST", "spaces", parameters)
      .then((data: string) => data)
      .catch((err) => {
        console.log(err);
        throw err;
      });
  }

  public static update(
    guid: string,
    parameters: SpacesParams
  ): Promise<any> {
    return DBSService.sendRequest<any>("PUT", `spaces/${guid}`, parameters)
      .then((data: string) => {
        if (!data) {
          return null;
        }
        return JSON.parse(data);
      })
      .catch((err) => {
        console.log(err);
        throw err;
      });
  }
}

export default SpaceService;
