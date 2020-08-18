module.exports = {
  FILEPATH: {
    SERVER: '/config/servers.json',
    CONFIG_DIR: '/config'
  },
  RESERVED: {
    BASE: 'base',
    MAIN: 'main',
    SERVERS: 'servers',
    ENV: 'env',
    CPU: 'cpu',
    ENV_DEV: 'development',
    ENV_STG: 'stage',
    ENV_PRO: 'production',
    ALL: 'all',
    SERVER_ID: 'serverId',
    CURRENT_SERVER: 'curServer',
  },
 TIME: {
   TIME_WAIT_STOP: 3 * 1000,
   TIME_WAIT_KILL: 5 * 1000,
   TIME_WAIT_RESTART: 5 * 1000,
   TIME_WAIT_COUNTDOWN: 10 * 1000,
   TIME_WAIT_MASTER_KILL: 2 * 60 * 1000,
   TIME_WAIT_MONITOR_KILL: 2 * 1000,
   TIME_WAIT_PING: 30 * 1000,
   TIME_WAIT_MAX_PING: 5 * 60 * 1000,
   DEFAULT_UDP_HEARTBEAT_TIME: 20 * 1000,
   DEFAULT_UDP_HEARTBEAT_TIMEOUT: 100 * 1000
 }
};