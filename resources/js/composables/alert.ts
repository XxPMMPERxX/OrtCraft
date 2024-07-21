import { ref, type Ref } from 'vue';

interface Alert {
  key?: string,
  /**
   * アラートのタイプ
   */
  color?: string | 'default' | 'info' | 'success' | 'warning' | 'error',
  /**
   * 表示するメッセージ
   */
  message?: string,
  /**
   * 閉じるボタンを表示するかどうか
   */
  closeable?: boolean,
  /**
   * 閉じるまでの秒数
   * 0以下で無効 その場合closeableが必要
   * clseableも無効の場合は５秒後に削除
   */
  close_at?: number,
};

const alerts: Ref<Alert[]> = ref<Alert[]>([]);

const pushAlert = ({ message, color = 'default', closeable = false, close_at = 0}: Alert) => {
  close_at = close_at > 0 ? close_at : 0;
  const alert: Alert = {
    key: crypto.randomUUID(),
    color,
    message,
    closeable,
    close_at,
  };

  alerts.value.push(alert);

  // 閉じるボタン非表示かつ、閉じる秒数も設定されていない場合は、強制的に5秒後に削除
  if (!closeable && !close_at) {
    console.log('test');
    close_at = 5;
  }

  if (close_at) {
    /**
     * close_at秒後に消す
     */
    setTimeout(() => {
      closeAlert(alert);
    }, close_at * 1000);
  }

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
