import React from 'react';
import InputField from '../../../shared/components/formFields/InputField';
import { OrderStatusEnum } from '../../../shared/enums/order/OrderStatusEnum';

interface OrderFieldInputProps {
  deliveryAddress: string;
  contactPhone: string;
  deliveryTime: string | undefined;
  status: OrderStatusEnum;
  statusOptions: { value: string; label: string }[];
  setDeliveryAddress: (value: string) => void;
  setContactPhone: (value: string) => void;
  setDeliveryTime: (value: string) => void;
  setStatus: (value: OrderStatusEnum) => void;
  isEditMode: boolean;
}

const OrderFieldInput: React.FC<OrderFieldInputProps> = ({
  deliveryAddress,
  contactPhone,
  deliveryTime,
  status,
  statusOptions,
  setDeliveryAddress,
  setContactPhone,
  setDeliveryTime,
  setStatus,
  isEditMode,
}) => {
  return (
    <div>
      <InputField
        id="deliveryAddress"
        label="Delivery Address"
        value={deliveryAddress}
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setDeliveryAddress(e.target.value)}
        required
      />
      <InputField
        id="contactPhone"
        label="Contact Phone"
        type="tel"
        value={contactPhone}
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setContactPhone(e.target.value)}
        required
      />
      <InputField
        id="deliveryTime"
        label="Delivery Time"
        type="datetime-local"
        value={deliveryTime}
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setDeliveryTime(e.target.value)}
      />
      <InputField
        id="status"
        label="Status"
        value={status}
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setStatus(e.target.value as OrderStatusEnum)}
        required={isEditMode}
        options={statusOptions}
      />
    </div>
  );
};

export default OrderFieldInput;