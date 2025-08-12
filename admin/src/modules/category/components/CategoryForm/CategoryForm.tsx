import React, { useState, useEffect } from 'react';
import styles from '../../../shared/styles/form.module.css';
import { useCategoryStore } from '../../../../store/categoryStore';
import InputField from '../../../shared/components/formFields/InputField';
import TextAreaField from '../../../shared/components/formFields/TextAreaField';

interface Category {
  id: number;
  name: string;
  description?: string;
}

interface CategoryFormProps {
  open: boolean;
  onClose: () => void;
  categoryToEdit: Category | null;
}

export const CategoryForm: React.FC<CategoryFormProps> = ({ open, onClose, categoryToEdit }) => {
  const { createCategory, updateCategory } = useCategoryStore();
  const [name, setName] = useState('');
  const [description, setDescription] = useState('');

  useEffect(() => {
    if (categoryToEdit) {
      setName(categoryToEdit.name);
      setDescription(categoryToEdit.description || '');
    } else {
      setName('');
      setDescription('');
    }
  }, [categoryToEdit, open]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
   
    if (categoryToEdit) {
      await updateCategory(categoryToEdit.id, {name, description});
    } else {
      await createCategory({name, description});
    }
    onClose();
  };

  if (!open) return null;

  return (
    <div className={styles.backdrop}>
      <div className={styles.dialog}>
        <h2 className={styles.title}>
          {categoryToEdit ? 'Edit Category' : 'Create Category'}
        </h2>
        <div className={styles.content}>
          <form onSubmit={handleSubmit}>
            <InputField
              id="name"
              label="Name"
              value={name}
              onChange={(e: any) => setName(e.target.value)}
              required
            />
            <TextAreaField
              id="description"
              name="description"
              label="Description"
              value={description}
              onChange={(e: any) => setDescription(e.target.value)}
            />
          </form>
        </div>
        <div className={styles.actions}>
          <button type="button" className={styles.button} onClick={onClose}>
            Cancel
          </button>
          <button onClick={handleSubmit} className={`${styles.button} ${styles.primaryButton}`}>
            {categoryToEdit ? 'Save' : 'Create'}
          </button>
        </div>
      </div>
    </div>
  );
};