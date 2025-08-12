'use client'

import useAuthStore from '@/store/authStore';
import React, { useEffect, useState } from 'react';
import styles from './authInitializer.module.css'

export const AuthInitializer: React.FC = () => {
  const { initialize, isInitialized, isLoading } = useAuthStore();
  const [minDisplayTimeElapsed, setMinDisplayTimeElapsed] = useState(false);

  useEffect(() => {
    setMinDisplayTimeElapsed(true);

    const timer = setTimeout(() => {
      setMinDisplayTimeElapsed(false);
    }, 500);

    if (!isInitialized && !isLoading) {
      initialize();
    }

    return () => clearTimeout(timer);
  }, []);

  if (!isInitialized || minDisplayTimeElapsed) {
    return (
      <div className={styles.authInitializerContainer}>
        <div className={styles.authInitializerSpinner}></div>
      </div>
    );
  }

  return null;
};