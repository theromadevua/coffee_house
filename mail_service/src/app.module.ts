import { Module } from '@nestjs/common';
import { MailerModule } from '@nestjs-modules/mailer';
import { OrdersController } from './order/orders.controller';
import { QueueModule } from './queue/queue.module';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { TelegramModule } from './telegram/telegram.module';

@Module({
  imports: [
    ConfigModule.forRoot({
      isGlobal: true, 
      envFilePath: '.env', 
    }),
    TelegramModule,
    QueueModule,
    MailerModule.forRootAsync({
      imports: [ConfigModule], 
      inject: [ConfigService],
      useFactory: (configService: ConfigService) => ({
        transport: {
          host: 'smtp.gmail.com',
          port: 587,
          secure: false,
          auth: {
            user: "theromadevua@gmail.com",
            pass: "iiff gvcj zpfh jejq ",
          },
        },
        defaults: {
          from: `"No Reply" <${configService.get<string>('EMAIL_USERNAME')}>`,
        },
      }),
    }),
  ],
  controllers: [OrdersController],
  providers: [],
})
export class AppModule {}