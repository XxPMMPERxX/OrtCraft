import ServerIdentity from "./ServerIdentity";

export default interface server {
  id: string,
  name?: string;
  identity?: ServerIdentity,
  description?: string;
}
