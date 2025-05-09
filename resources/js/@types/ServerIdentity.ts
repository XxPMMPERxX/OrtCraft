export default interface ServerIdentity {
  id: string;
  label?: string,
  auth_code: string;
  address?: string;
  je_port?: number;
  be_port?: number;
  is_verify: string;
}
