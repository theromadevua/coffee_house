'use client';

import { useEffect } from 'react';
import { useCategoryStore } from '@/store/categoryStore';
import { useQueryManager } from '@/app/(main)/hooks/useQueryManager';
import styles from './categories.module.css';
import CategoriesArray from './CategoriesArray/CategoriesArray';

export default function Categories() {
  const { categories, isLoading, error, fetchCategories } = useCategoryStore();
  const { activeCategory, toggleCategory, clearCategory } = useQueryManager();

  useEffect(() => {
    fetchCategories();
  }, [fetchCategories]);

  if (isLoading) {
    return <div className={`${styles.categoriesSection} ${styles.statusMessage} ${styles.loading}`}>Loading categories...</div>;
  }

  if (error) {
    return <div className={`${styles.categoriesSection} ${styles.statusMessage} ${styles.error}`}>Error: {error}</div>;
  }

  return (
    <div className={styles.categoriesSection}>
        {categories.length === 0 ? (
          <p className={`${styles.statusMessage} ${styles.empty}`}>No categories found.</p>
        ) : (
            <CategoriesArray categories={categories} activeCategory={activeCategory} toggleCategory={toggleCategory} clearCategory={clearCategory}/>
          )
        }
    </div>
  );
}