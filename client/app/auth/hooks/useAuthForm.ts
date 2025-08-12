import { useState } from 'react';
import { LoginCredentials, RegisterData } from '@/services/authService';
import useAuthStore from '@/store/authStore';

type AuthMode = 'login' | 'register';

export const useAuthForm = (initialMode: AuthMode = 'login') => {
  const { login, register, isLoading, error } = useAuthStore();
  const [isLoginMode, setIsLoginMode] = useState(initialMode === 'login');

  const [loginCredentials, setLoginCredentials] = useState<LoginCredentials>({
    email: '',
    password: '',
  });

  const [registerData, setRegisterData] = useState<RegisterData>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  const toggleMode = () => setIsLoginMode(prev => !prev);

  const handleLoginChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setLoginCredentials(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleRegisterChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setRegisterData(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSubmit = async (
    e: React.FormEvent,
    onSuccess?: () => void,
    onError?: (err: string) => void
  ) => {
    e.preventDefault();
    if (isLoginMode) {
      const result = await login(loginCredentials);
      result.success ? onSuccess?.() : onError?.(result.message || 'Login failed');
    } else {
      if (registerData.password !== registerData.password_confirmation) {
        onError?.('Passwords do not match');
        return;
      }
      const result = await register(registerData);
      result.success ? onSuccess?.() : onError?.(result.message || 'Registration failed');
    }
  };

  return {
    isLoginMode,
    isLoading,
    error,
    loginCredentials,
    registerData,
    handleLoginChange,
    handleRegisterChange,
    handleSubmit,
    toggleMode,
  };
};
