import { Controller } from '@nestjs/common';
import { EventPattern, Payload } from '@nestjs/microservices';
import { NotificationService } from '../queue/notification.service';

@Controller()
export class OrdersController {
  constructor(private readonly notificationService: NotificationService) {}

  @EventPattern('order.created')
  async handleOrderCreated(@Payload() data: any) {
    console.log('Order received:', data);
    try {
      await this.notificationService.sendOrderConfirmationEmail(data);
      await this.notificationService.sendOrderConfirmationSMS(data);
      console.log(`Notifications sent for order #${data.id}`);
    } catch (error) {
      console.error('Error sending notifications:', error);
    }
  }
}