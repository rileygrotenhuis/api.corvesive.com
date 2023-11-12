import HttpFactory from '~/services/factory';

interface ICreateOrUpdatePaystubRequest {
  issuer: String;
  amount: Number;
  notes: String;
}

class PaystubService extends HttpFactory {
  async getPaystubs(): Promise {
    return await this.call('GET', '/paystubs');
  }

  async createPaystub(payload: ICreateOrUpdatePaystubRequest): Promise {
    return await this.call('POST', '/paystubs', payload);
  }

  async updatePaystub(
    id: Number,
    payload: ICreateOrUpdatePaystubRequest
  ): Promise {
    return await this.call('PUT', `/paystubs/${id}`, payload);
  }

  async deletePaystub(id: Number): Promise {
    return await this.call('DELETE', `/paystubs/${id}`, payload);
  }
}

export default PaystubService;