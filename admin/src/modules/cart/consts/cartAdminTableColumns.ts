import { ICart } from "../../shared/interfaces/cart/ICart";

export const cartAdminTableColumns = [
  { header: "User ID", accessor: (cart: ICart) => cart.user_id },
  {
    header: "Items",
    accessor: (cart: ICart) =>
      cart.items.length > 0
        ? cart.items
            .map((item) => `${item.name} (Qty: ${item.pivot?.quantity || 0})`)
            .join(", ")
        : "No items",
  },
  { header: "Total Price", accessor: (cart: ICart) => `$${cart.total_price}` },
];