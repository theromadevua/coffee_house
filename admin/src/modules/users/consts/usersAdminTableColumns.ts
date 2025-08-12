
import { IUser } from '../../shared/interfaces/user/IUser';

export const userAdminPageColumns = [
  {
    header: 'ID',
    accessor: (u: IUser) => u.id
  },
  {
    accessor: (u: IUser) => u.name,
    header: 'Name',
  },
  {
    accessor: (u: IUser) => u.email,
    header: 'Email',
  },
  {
    accessor: (u: IUser) => u.role,
    header: 'Role',
  },
];
