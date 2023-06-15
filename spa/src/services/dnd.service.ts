import { HttpMethod } from "../types/HttpMethod";

class DBSService {
  public static apiUrl = process.env.REACT_APP_DND_API;

  public static sendRequest<T>(
    method: HttpMethod,
    url: string,
    body?: T
  ): Promise<any> {
    const requestOptions: RequestInit = {
      method,
      headers: { "Content-Type": "application/json" },
      body: body ? JSON.stringify(body) : undefined,
    };
    const requestUrl = `${this.apiUrl}/${url}`;

    return fetch(requestUrl, requestOptions)
      .then((response) => {
        if (response.ok) {
          if (response.status === 204) {
            return;
          }
          return response.json();
        }
        throw new Error(`${response.status}: ${response.statusText}`);
      })
      .catch((err) => {
        console.log(err);
        throw err;
      });
  }
}

export default DBSService;
