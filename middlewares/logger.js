
const logger = (req, res, next) => {
  const timestamp = new Date().toISOString();
  console.log(`[${timestamp}] ${req.method} ${req.url}`);

  if (Object.keys(req.body || {}).length > 0) {
    console.log("Body:", req.body);
  }

  next(); 
};

export default logger;
