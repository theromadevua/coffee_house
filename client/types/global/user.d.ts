export {};

declare global {
  interface User {
    id: number;
    name: string;
    email: string;
    role: 'user' | 'admin';
    created_at: string;
    updated_at: string;
    gallery?: any;
  }

  interface MeResponse {
    data: User;
  }

  interface RegisterResponse {
    data: User;
  }
}
