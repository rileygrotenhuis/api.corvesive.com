import { defineStore } from 'pinia';
import type {
  IBillResource,
  IPayPeriodBillResource,
} from '~/http/resources/bills.resource';

export const useBillStore = defineStore('useBillStore', {
  state: () => ({
    bills: [] as IBillResource[],
    payPeriodBills: [] as IPayPeriodBillResource[],
    payPeriodBill: {} as IPayPeriodBillResource,
  }),
  actions: {
    async getBills(): Promise<IBillResource[]> {
      this.bills = (await useNuxtApp().$api.bills.getBills()).data;

      return this.bills;
    },
    async getPayPeriodBills(
      payPeriodId: number
    ): Promise<IPayPeriodBillResource[]> {
      this.payPeriodBills = (
        await useNuxtApp().$api.bills.getPayPeriodBills(payPeriodId)
      ).data;

      return this.payPeriodBills;
    },
    async getPayPeriodBill(
      payPeriodId: number,
      payPeriodBillId: number
    ): Promise<IPayPeriodBillResource> {
      this.payPeriodBill = (
        await useNuxtApp().$api.bills.getPayPeriodBill(
          payPeriodId,
          payPeriodBillId
        )
      ).data;

      return this.payPeriodBill;
    },
  },
});
