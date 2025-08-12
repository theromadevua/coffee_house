import React from 'react';
import styles from '../../../shared/styles/form.module.css';
import { IUser } from '../../../shared/interfaces/user/IUser';
import { useUserForm } from '../../hooks/useUserForm';
import { Role } from '../../../shared/enums/users/Role';

interface UserFormProps {
  open: boolean;
  onClose: () => void;
  userToEdit: IUser | null;
}

export const UserForm: React.FC<UserFormProps> = ({
  open,
  onClose,
  userToEdit,
}) => {
  const {
    name,
    email,
    password,
    role,
    isEditMode,
    error,
    setName,
    setEmail,
    setPassword,
    setRole,
    handleSubmit,
  } = useUserForm({ userToEdit, onClose });

  if (!open) return null;

  const roleOptions = Object.values(Role).map((r) => ({
    value: r,
    label: r.charAt(0).toUpperCase() + r.slice(1),
  }));

  return (
    <div className={styles.backdrop}>
      <div className={styles.dialog}>
        <div className={styles.content}>
            <h2 className={styles.title}>
            {isEditMode ? 'Edit User' : 'Create User'}
            </h2>
            
            {error && <p className={styles.error}>{error}</p>}

            <form onSubmit={() => alert('a')} className={styles.form}>
            <div className={styles.formGroup}>
                <label htmlFor="name" className={styles.label}>Name</label>
                <input
                id="name"
                type="text"
                value={name}
                onChange={(e) => setName(e.target.value)}
                className={styles.input}
                required
                />
            </div>
            <div className={styles.formGroup}>
                <label htmlFor="email" className={styles.label}>Email</label>
                <input
                id="email"
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className={styles.input}
                required
                />
            </div>
            <div className={styles.formGroup}>
                <label htmlFor="password" className={styles.label}>
                Password {isEditMode && '(leave blank to keep current)'}
                </label>
                <input
                id="password"
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                className={styles.input}
                required={!isEditMode}
                />
            </div>
            <div className={styles.formGroup}>
                <label htmlFor="role" className={styles.label}>Role</label>
                <select
                id="role"
                value={role}
                onChange={(e) => setRole(e.target.value as Role)}
                className={styles.select}
                >
                {roleOptions.map((option) => (
                    <option key={option.value} value={option.value}>
                    {option.label}
                    </option>
                ))}
                </select>
            </div>
            </form>
        </div>
        <div className={styles.actions}>
            <button type="button" className={styles.button} onClick={onClose}>
              Cancel
            </button>
            <button
              type="submit"
              className={`${styles.button} ${styles.primaryButton}`}
              onClick={(e) => handleSubmit(e)}
            >
              {isEditMode ? 'Save Changes' : 'Create User'}
            </button>
        </div>
      </div>
    </div>
  );
};