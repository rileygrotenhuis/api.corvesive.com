import { defineStore } from 'pinia';
import type {
  IPayPeriodPaystubResource,
  IPaystubResource,
} from '~/http/resources/paystubs.resource';

export const usePaystubStore = defineStore('usePaystubStore', {
  state: () => ({
    paystubs: [] as IPaystubResource[],
    payPeriodPaystubs: [] as IPayPeriodPaystubResource[],
  }),
  actions: {
    async getPaystubs(): Promise<IPaystubResource[]> {
      this.paystubs = await useNuxtApp().$api.paystubs.getPaystubs();

      return this.paystubs;
    },
    async getPayPeriodPaystubs(
      payPeriodId: Number
    ): Promise<IPayPeriodPaystubResource[]> {
      this.payPeriodPaystubs =
        await useNuxtApp().$api.paystubs.getPayPeriodPaystubs(payPeriodId);

      return this.payPeriodPaystubs;
    },
  },
});
