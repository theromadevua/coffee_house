'use client';

import React, { useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { AuthForm } from '../components/AuthFrom/AuthForm';
import useAuthStore from '@/store/authStore';
import styles from './auth.module.css';

export default function AuthPage() {
  const router = useRouter();
  const params = useParams();
  const {isAuthenticated} = useAuthStore();

  const mode = params.mode === 'register' ? 'register' : 'login';

  useEffect(() => {
    if (isAuthenticated) {
      router.push('/profile');
    }
  }, [isAuthenticated, router]);

  const handleSuccess = () => {
    if (mode === 'register') {
      router.push('/auth/login');
    }
  };

  return (
    <div className={styles.authPage}>
      <AuthForm initialMode={mode} onSuccess={handleSuccess} />
    </div>
  );
}