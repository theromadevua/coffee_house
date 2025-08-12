import React from 'react';
import styles from './adminPage.module.css';
import { Pencil, Trash2 } from 'lucide-react';

interface Column<T> {
  header: string;
  accessor: (row: T, index?: number) => React.ReactNode;
}

interface AdminTableProps<T> {
  title: string;
  data: T[];
  columns: Column<T>[];
  onEdit?: (row: T) => void;
  onDelete?: (row: T) => void;
}

export function AdminTable<T>({ title, data, columns, onEdit, onDelete }: AdminTableProps<T>) {
  return (
    <div className={styles.section}>
      <div className={styles.sectionTitle}>{title}</div>
      <table className={styles.table}>
        <thead>
          <tr className={styles.tr}>
            {columns.map((col, idx) => (
              <th key={idx} className={styles.th}>{col.header}</th>
            ))}
            {(onEdit || onDelete) && <th className={styles.th}>Actions</th>}
          </tr>
        </thead>
        <tbody>
          {data.map((row, rowIndex) => (
            <tr key={rowIndex} className={styles.tr}>
              {columns.map((col, colIndex) => (
                <td key={colIndex} className={styles.td}>{col.accessor(row, rowIndex)}</td>
              ))}
              {(onEdit || onDelete) && (
                <td className={styles.td}>
                  <div className={styles.actionsCell}>
                    {onEdit && (
                      <Pencil
                        className={`${styles.icon} ${styles.editIcon}`}
                        onClick={() => onEdit(row)}
                        role="button"
                        tabIndex={0}
                        aria-label="Edit row"
                        onKeyDown={(e) => { if (e.key === 'Enter') onEdit(row); }}
                      />
                    )}
                    {onDelete && (
                      <Trash2
                        className={`${styles.icon} ${styles.deleteIcon}`}
                        onClick={() => onDelete(row)}
                        role="button"
                        tabIndex={0}
                        aria-label="Delete row"
                        onKeyDown={(e) => { if (e.key === 'Enter') onDelete(row); }}
                      />
                    )}
                  </div>
                </td>
              )}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}