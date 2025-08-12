

export interface IUser {
    id: number;
    name: string;
    email: string;
    role: 'user' | 'admin';
    created_at: string;
    updated_at: string;
    gallery?: any;
    password?: string,
    password_confirmation?: string
  }