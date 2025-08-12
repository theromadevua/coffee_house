'use client';

import React from 'react';
import { useAuthForm } from '../../hooks/useAuthForm';
import styles from './authForm.module.css';
import Input from '../Input/Input';
import Link from 'next/link';
import { useGoogleLogin } from '../../hooks/useGoogleLogin';
import Image from 'next/image';
import { Home } from 'lucide-react';

interface AuthFormProps {
  onSuccess?: () => void;
  onError?: (error: string) => void;
  initialMode?: 'login' | 'register';
  errorMessage?: string;
}

export const AuthForm: React.FC<AuthFormProps> = ({
  onSuccess,
  onError,
  errorMessage,
  initialMode = 'login',
}) => {
  const {
    isLoginMode,
    isLoading,
    loginCredentials,
    registerData,
    handleLoginChange,
    handleRegisterChange,
    handleSubmit,
  } = useAuthForm(initialMode);
  const { handleGoogleLogin } = useGoogleLogin(onError);

  return (
    <div className={styles.authPageContainer}>
      <div>
        <h2 className={styles.authPageTitle}>Sign in to your account</h2>
      </div>
      {errorMessage && (
        <div className={styles.errorMessage}>{errorMessage}!</div>
      )}
      <div className={styles.authFormContainer}>
        <form
          onSubmit={(e) => handleSubmit(e, onSuccess, onError)}
          className={styles.authForm}
        >
          {!isLoginMode && (
            <Input
              label="Name"
              name="name"
              value={registerData.name}
              onChange={handleRegisterChange}
              required
            />
          )}
          <Input
            label="Email"
            type="email"
            name="email"
            value={isLoginMode ? loginCredentials.email : registerData.email}
            onChange={isLoginMode ? handleLoginChange : handleRegisterChange}
            required
          />
          <Input
            label="Password"
            type="password"
            name="password"
            value={isLoginMode ? loginCredentials.password : registerData.password}
            onChange={isLoginMode ? handleLoginChange : handleRegisterChange}
            required
          />
          {!isLoginMode && (
            <Input
              label="Confirm Password"
              type="password"
              name="password_confirmation"
              value={registerData.password_confirmation}
              onChange={handleRegisterChange}
              required
            />
          )}
          <button
            type="submit"
            disabled={isLoading}
            className={styles.submitButton}
          >
            {isLoading
              ? isLoginMode
                ? 'Logging in...'
                : 'Creating account...'
              : isLoginMode
                ? 'Login'
                : 'Register'}
          </button>
          <div className={styles.toggleContainer}>
            <Link href={initialMode !== `login` ? `/auth/login` : `/auth/register`}>
              <button type="button" className={styles.toggleButton}>
                {isLoginMode
                  ? "Don't have an account?"
                  : 'Already have an account?'}
              </button>
            </Link>
            <Link href={`/`}>
              <button type="button" className={styles.homeLink}>
                <Home/>
              </button>
            </Link>
          </div>
        </form>
        <hr className={styles.line}/>
          <div className={styles.googleButtonContainer}>
            <button
              type="button"
              onClick={handleGoogleLogin}
              className={styles.googleButton}
            >
              <Image src={'/google.svg'} alt={''} width={24} height={24}/>
            </button>
          </div>
      </div>
    </div>
  );
};