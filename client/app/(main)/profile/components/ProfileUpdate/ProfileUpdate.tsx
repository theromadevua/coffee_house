import styles from './ProfileUpdate.module.css';

interface UpdateFormProps {
  formData: {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
  };
  errors: { [key: string]: string };
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  onSubmit: (e: React.FormEvent) => void;
}

const UpdateForm: React.FC<UpdateFormProps> = ({ formData, errors, onChange, onSubmit }) => (
  <form onSubmit={onSubmit} className={styles.profileForm}>
    <div className={styles.formGroup}>
      <label htmlFor="name">Name</label>
      <input
        id="name"
        name="name"
        type="text"
        value={formData.name}
        onChange={onChange}
        className={styles.formInput}
      />
      {errors.name && <p className={styles.error}>{errors.name}</p>}
    </div>
    <div className={styles.formGroup}>
      <label htmlFor="email">Email</label>
      <input
        id="email"
        name="email"
        type="email"
        value={formData.email}
        onChange={onChange}
        className={styles.formInput}
      />
      {errors.email && <p className={styles.error}>{errors.email}</p>}
    </div>
    <div className={styles.formGroup}>
      <label htmlFor="password">Password</label>
      <input
        id="password"
        name="password"
        type="password"
        value={formData.password}
        onChange={onChange}
        className={styles.formInput}
      />
      {errors.password && <p className={styles.error}>{errors.password}</p>}
    </div>
    <div className={styles.formGroup}>
      <label htmlFor="password_confirmation">Password confirmation</label>
      <input
        id="password_confirmation"
        name="password_confirmation"
        type="password"
        value={formData.password_confirmation}
        onChange={onChange}
        className={styles.formInput}
      />
      {errors.password_confirmation && <p className={styles.error}>{errors.password_confirmation}</p>}
    </div>
    <button type="submit" className={styles.submitButton}>
      Save Changes
    </button>
  </form>
);

export default UpdateForm;