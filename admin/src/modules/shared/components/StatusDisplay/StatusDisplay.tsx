import React from 'react';
import styles from './statusDisplay.module.css';

interface StatusDisplayProps {
  isLoading: boolean;
  error: string | null;
}

export const StatusDisplay: React.FC<StatusDisplayProps> = ({ isLoading, error }) => {
  return (
    <>
      {isLoading && <p className={styles.loading}>Loading...</p>}
      {error && <p className={styles.error}>Error: {error}</p>}
    </>
  );
};