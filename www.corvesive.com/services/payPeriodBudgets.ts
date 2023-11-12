class PayPeriodBudgetsService {
  async getPayPeriodBudgets(payPeriodId: Number) {
    const response = await fetch(
      `${useRuntimeConfig().public.apiUrl}/pay-periods/${payPeriodId}/budgets`,
      {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${useCookie('corvesive_access_token').value}`,
        },
      }
    );
    return await response.json();
  }

  async attachBudgetToPayPeriod(
    payPeriodId: Number,
    budgetId: Number,
    totalBalance: Number
  ) {
    const response = await fetch(
      `${
        useRuntimeConfig().public.apiUrl
      }/pay-periods/${payPeriodId}/budgets/${budgetId}`,
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${useCookie('corvesive_access_token').value}`,
        },
        body: JSON.stringify({
          total_balance: totalBalance * 100,
        }),
      }
    );
    return await response.json();
  }

  async updatePayPeriodBudget(
    payPeriodId: Number,
    budgetId: Number,
    totalBalance: Number,
    remainingBalance: Number
  ) {
    const response = await fetch(
      `${
        useRuntimeConfig().public.apiUrl
      }/pay-periods/${payPeriodId}/budgets/${budgetId}`,
      {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${useCookie('corvesive_access_token').value}`,
        },
        body: JSON.stringify({
          total_balance: totalBalance * 100,
          remaining_balance: remainingBalance * 100,
        }),
      }
    );
    return await response.json();
  }

  async detachBudgetFromPayPeriod(payPeriodId: Number, budgetId: Number) {
    const response = await fetch(
      `${
        useRuntimeConfig().public.apiUrl
      }/pay-periods/${payPeriodId}/budgets/${budgetId}`,
      {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${useCookie('corvesive_access_token').value}`,
        },
      }
    );
    return await response.json();
  }
}

export default PayPeriodBudgetsService;