import styles from '../../styles/form.module.css';

interface SelectFieldProps {
  id: string;
  label: string;
  value: string;
  onChange: (e: React.ChangeEvent<HTMLSelectElement>) => void;
  options: { id: number; name: string }[];
}

export const SelectField: React.FC<SelectFieldProps> = ({ id, label, value, onChange, options }) => (
  <div className={styles.formGroup}>
    <label htmlFor={id} className={styles.label}>{label}</label>
    <select
      name={id}
      id={id}
      className={styles.select}
      value={value}
      onChange={onChange}
      required
    >
      <option value="" disabled>Select a category</option>
      {options.map(option => (
        <option key={option.id} value={option.id}>{option.name}</option>
      ))}
    </select>
  </div>
);

export default SelectField