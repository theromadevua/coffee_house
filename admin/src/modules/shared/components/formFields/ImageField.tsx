import React, { useState, useEffect } from 'react';
import styles from '../../styles/form.module.css';

interface ImageFieldProps {
  id: string;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

export const ImageField: React.FC<ImageFieldProps> = ({ id, onChange }) => (
  <div className={styles.formGroup}>
    <label htmlFor={id} className={styles.label}>Image</label>
    <input
      id={id}
      type="file"
      className={styles.input}
      onChange={onChange}
      accept="image/*"
    />
  </div>
);

export default ImageField;