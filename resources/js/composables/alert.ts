import { ref, type Ref } from 'vue';

interface Alert {
  key?: string,
  color: string | 'default' | 'info' | 'success' | 'warning' | 'error',
  message: string,
  closeable: boolean,
};

const alerts: Ref<Alert[]> = ref<Alert[]>([]);

const pushAlert = (alert: Alert) => {
  alert.key = crypto.randomUUID();
  alerts.value.push(alert);

  /**
   * 5秒後に消す
   */
  setTimeout(() => {
    closeAlert(alert);
  }, 5000);
};

const closeAlert = (alert: Alert) => {
  alerts.value = alerts.value.filter((item) => item.key !== alert.key);
}

export {
  type Alert,
  pushAlert,
  closeAlert,
  alerts,
};
