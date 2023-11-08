class PayPeriodDashboardService {
  async getPayPeriodDashboardMetrics(payPeriodId) {
    const response = await fetch(
      `${useRuntimeConfig().public.apiUrl}/pay-periods/${payPeriodId}/dashboard`,
      {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${useCookie('corvesive_access_token').value}`
        }
      }
    );
    return await response.json();
  }
}

export default PayPeriodDashboardService;
