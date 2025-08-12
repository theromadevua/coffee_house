import React from 'react';
import { AuthInitializer } from '../AuthInitializer/AuthInitializer';

interface AuthProviderProps {
  children: React.ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  return (
    <>
      <AuthInitializer />
      {children}
    </>
  );
};