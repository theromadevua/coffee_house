import React, { ChangeEvent } from 'react';
import styles from '../../styles/form.module.css';

interface BaseInputFieldProps {
  id: string;
  label: string;
  value: string | number | undefined;
  required?: boolean;
  min?: string;
  stylesConfig?: {
    formGroup: string;
    label: string;
    input: string;
  };
}

interface InputProps extends BaseInputFieldProps {
  type?: string;
  step?: string;
  onChange: (e: ChangeEvent<HTMLInputElement>) => void;
}

interface SelectProps extends BaseInputFieldProps {
  options: { value: string; label: string }[];
  onChange: (e: ChangeEvent<HTMLSelectElement>) => void;
}

type InputFieldProps = InputProps | SelectProps;

export const InputField: React.FC<InputFieldProps> = (props) => {
  const { id, label, value, required, onChange, stylesConfig } = props;

  const formGroupClass = stylesConfig?.formGroup || styles.formGroup;
  const labelClass = stylesConfig?.label || styles.label;
  const inputClass = stylesConfig?.input || styles.input;

  return (
    <div className={formGroupClass}>
      <label htmlFor={id} className={labelClass}>
        {label}
      </label>
      {'options' in props ? (
        <select
          id={id}
          name={id}
          className={inputClass}
          value={value}
          onChange={onChange as (e: ChangeEvent<HTMLSelectElement>) => void}
          required={required}
        >
          {props.options.map((option) => (
            <option key={option.value} value={option.value}>
              {option.label}
            </option>
          ))}
        </select>
      ) : (
        <input
          id={id}
          name={id}
          type={props.type || 'text'}
          className={inputClass}
          value={value}
          onChange={onChange as (e: ChangeEvent<HTMLInputElement>) => void}
          required={required}
          step={props.step}
          min={props.min && props.min}
        />
      )}
    </div>
  );
};

export default InputField;