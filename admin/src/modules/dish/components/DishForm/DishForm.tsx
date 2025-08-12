import React, { useState, useEffect } from 'react';
import styles from '../../../shared/styles/form.module.css';
import { useDishStore } from '../../../../store/dishStore';
import { useCategoryStore } from '../../../../store/categoryStore';
import TextAreaField from '../../../shared/components/formFields/TextAreaField';
import InputField from '../../../shared/components/formFields/InputField';
import SelectField from '../../../shared/components/formFields/SelectField';
import ImageField from '../../../shared/components/formFields/ImageField';
import { IDish } from '../../../shared/interfaces/dish/IDish';


interface DishFormProps {
  open: boolean;
  onClose: () => void;
  dishToEdit: IDish | null;
}

interface FormState {
  name: string;
  description: string;
  price: string;
  category_id: string;
}

const initialFormState: FormState = {
  name: '',
  description: '',
  price: '',
  category_id: '',
};

export const DishForm: React.FC<DishFormProps> = ({ open, onClose, dishToEdit }) => {
  const { createDish, updateDish } = useDishStore();
  const { categories, fetchCategories } = useCategoryStore();
  const [formState, setFormState] = useState<FormState>(initialFormState);
  const [imageFiles, setImageFiles] = useState<File[]>([]);
  const [imageInputs, setImageInputs] = useState([0]);

  useEffect(() => {
    fetchCategories();
  }, [fetchCategories]);

  useEffect(() => {
    if (dishToEdit) {
      setFormState({
        name: dishToEdit.name,
        description: dishToEdit.description || '',
        price: dishToEdit.price.toString(),
        category_id: dishToEdit.category?.id.toString() || '',
      });
      setImageFiles([]);
      setImageInputs([0]);
    } else {
      setFormState(initialFormState);
      setImageFiles([]);
      setImageInputs([0]);
    }
  }, [dishToEdit, open]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormState(prev => ({ ...prev, [name]: value }));
  };

  const handleFileChange = (index: number) => (e: React.ChangeEvent<HTMLInputElement>) => {
     {
      setImageFiles(prev => {
        const newFiles = [...prev];
        if (e.target.files && e.target.files[0]) newFiles[index] = e.target.files[0];
        return newFiles;
      });
    }
  };

  const addImageInput = () => {
    setImageInputs(prev => [...prev, prev.length]);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const formData = new FormData();
    formData.append('name', formState.name);
    formData.append('description', formState.description);
    formData.append('price', formState.price);
    formData.append('category_id', formState.category_id);
    imageFiles.forEach(file => {
      formData.append('images[]', file);
    });
    
    const submissionPromise = dishToEdit
      ? updateDish(dishToEdit.id, formData)
      : createDish(formData);

    await submissionPromise;
    onClose();
  };

  if (!open) return null;

  return (
    <div className={styles.backdrop}>
      <div className={styles.dialog}>
        <h2 className={styles.title}>
          {dishToEdit ? 'Edit Dish' : 'Create Dish'}
        </h2>
        <div className={styles.content}>
          <form onSubmit={handleSubmit} className={styles.formGroup}>
            <InputField
              id="name"
              label="Name"
              value={formState.name}
              onChange={handleChange}
              required
              type="text"
            />
            <SelectField
              id="category_id"
              label="Category"
              value={formState.category_id}
              onChange={handleChange}
              options={categories}
            />
            <InputField
              id="price"
              label="Price"
              value={formState.price}
              onChange={handleChange}
              required
              type="number"
              step="0.01"
            />
            <TextAreaField
              id="description"
              name='description'
              label="Description"
              value={formState.description}
              onChange={handleChange}
            />
            {imageInputs.map((index) => (
              <ImageField
                key={index}
                id={`image-${index}`}
                onChange={handleFileChange(index)}
              />
            ))}
            <button
              type="button"
              className={styles.button}
              onClick={addImageInput}
            >
              Add Another Image
            </button>
          </form>
        </div>
        <div className={styles.actions}>
          <button type="button" className={styles.button} onClick={onClose}>
            Cancel
          </button>
          <button onClick={handleSubmit} className={`${styles.button} ${styles.primaryButton}`}>
            {dishToEdit ? 'Save' : 'Create'}
          </button>
        </div>
      </div>
    </div>
  );
};