import ServerIdentity from "./ServerIdentity";

export default interface server {
  id: string,
  name?: string;
  identities?: ServerIdentity[],
  identity?: ServerIdentity,
  description?: string;
}
