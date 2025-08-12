import React from 'react';
import styles from './searchBlock.module.css';

interface SearchBlockProps {
  value: string;
  onChange: (_: any) => any;
  onCreate?: () => void;
  placeholder?: string;
  title?: string;
}

export const SearchBlock: React.FC<SearchBlockProps> = ({
  value,
  onChange,
  onCreate,
  placeholder = 'Search...',
  title = 'Search',
}) => {
  const handleSearch = (e: React.ChangeEvent<HTMLInputElement>) => {
    onChange(e.target.value);
  };

  return (
    <div className={styles.searchBlock}>
      <div>
        <h3>{title}</h3>
        <input
          className={styles.searchInput}
          placeholder={placeholder}
          value={value}
          onChange={handleSearch}
        />
      </div>
      {onCreate && (
        <button className={`${styles.button} ${styles.primaryButton}`} onClick={onCreate}>
          Create
        </button>
      )}
    </div>
  );
};