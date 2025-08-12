import React from 'react';
import styles from '../../styles/form.module.css';

interface TextAreaFieldProps {
  id: string;
  name: string;
  label: string;
  value: string;
  onChange: (e: React.ChangeEvent<HTMLTextAreaElement>) => void;
}

const TextAreaField: React.FC<TextAreaFieldProps> = ({ id, name, label, value, onChange }) => (
  <div className={styles.formGroup}>
    <label htmlFor={id} className={styles.label}>{label}</label>
    <textarea
      id={id}
      name={name}
      className={styles.textarea}
      rows={4}
      value={value}
      onChange={onChange}
    />
  </div>
);

export default TextAreaField;