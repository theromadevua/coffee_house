import React from 'react';
import styles from './submitButton.module.css';

interface SubmitButtonProps {
  isLoading: boolean;
}

const SubmitButton: React.FC<SubmitButtonProps> = ({ isLoading }) => (
  <button type="submit" className={styles.submitButton} disabled={isLoading}>
    {isLoading ? 'Updating...' : 'Update Profile'}
  </button>
);

export default SubmitButton;
