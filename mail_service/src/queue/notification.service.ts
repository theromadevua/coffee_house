import { Injectable } from '@nestjs/common';
import { MailerService } from '@nestjs-modules/mailer';
import * as twilio from 'twilio';
import { TelegramService } from 'src/telegram/telegram.service';

@Injectable()
export class NotificationService {
  private twilioClient: twilio.Twilio;

  constructor(
    private readonly mailerService: MailerService, 
    private readonly telegramService: TelegramService
  ) {
    this.twilioClient = twilio(process.env.ACCOUNT_SID, process.env.AUTH_TOKEN);
  }

  async sendOrderConfirmationEmail(order: any) {
    console.log(order)
    const { id, email, total_amount, delivery_address, contact_phone, delivery_time, order_id } = order;
    const message = `📦 New order!
    \n👤 address: ${delivery_address}
    \n💵 Sum: ${total_amount}₽
    \n🧾 Order ID: ${order_id}`;

    await this.telegramService.sendMessage(message);

    await this.mailerService.sendMail({
      to: email, 
      subject: `Order #${id} Confirmation`,
      text: `
        Your order #${id} has been received!
        Total Amount: ${total_amount}
        Delivery Address: ${delivery_address}
        Delivery Time: ${new Date(delivery_time).toLocaleString()}
        Contact Phone: ${contact_phone}
      `,
    });
  }

  async sendOrderConfirmationSMS(order: any) {
    console.log('sendOrderConfirmationSMS')
    const { contact_phone, id, total_amount } = order;
    await this.twilioClient.messages.create({
      body: `Your order #${id} for ${total_amount} is being processed!`,
      from: process.env.PHONE_NUMBER,
      to: contact_phone,
    });
  }
}