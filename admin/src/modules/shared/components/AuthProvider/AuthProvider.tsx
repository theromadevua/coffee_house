import { useEffect } from 'react';
import useAuthStore from '../../../../store/authStore';

interface AuthProviderProps {
  children: React.ReactNode;
}

const AuthProvider = ({ children }: AuthProviderProps) => {
  const { initialize, isInitialized, isLoading } = useAuthStore();

  useEffect(() => {
    initialize();
  }, []);

  if (isLoading) {
    return <div></div>;
  }

  return <>{children}</>;
};

export default AuthProvider;