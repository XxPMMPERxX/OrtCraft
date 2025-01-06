interface server {
  id: string,
  name?: string;
  je_port?: number;
  be_port?: number;
  description?: string;
  types?: string;
  platform?: number;
  auth_code?: string;
  verified_at?: string;
}

export {
  type server
};
