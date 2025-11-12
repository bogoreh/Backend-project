const { ValidationError } = require('express-validator');
const mongoose = require('mongoose');
const cloudinary = require('cloudinary').v2;

/**
 * Custom Error Classes
 */
class AppError extends Error {
  constructor(message, statusCode, isOperational = true) {
    super(message);
    this.statusCode = statusCode;
    this.isOperational = isOperational;
    this.status = `${statusCode}`.startsWith('4') ? 'fail' : 'error';
    
    Error.captureStackTrace(this, this.constructor);
  }
}

class ValidationError extends AppError {
  constructor(errors, message = 'Validation failed') {
    super(message, 400);
    this.errors = errors;
  }
}

class AuthenticationError extends AppError {
  constructor(message = 'Authentication failed') {
    super(message, 401);
  }
}

class AuthorizationError extends AppError {
  constructor(message = 'Access denied') {
    super(message, 403);
  }
}

class NotFoundError extends AppError {
  constructor(resource = 'Resource') {
    super(`${resource} not found`, 404);
  }
}

class ConflictError extends AppError {
  constructor(message = 'Resource already exists') {
    super(message, 409);
  }
}

class RateLimitError extends AppError {
  constructor(message = 'Too many requests') {
    super(message, 429);
  }
}

/**
 * Global Error Handler Middleware
 */
const errorHandler = (err, req, res, next) => {
  let error = { ...err };
  error.message = err.message;
  error.statusCode = err.statusCode || 500;

  // Log error for development
  if (process.env.NODE_ENV === 'development') {
    console.error('🚨 Error Details:', {
      message: err.message,
      stack: err.stack,
      name: err.name,
      statusCode: error.statusCode
    });
  }

  // Mongoose bad ObjectId
  if (err.name === 'CastError') {
    const message = 'Resource not found';
    error = new NotFoundError(message);
  }

  // Mongoose duplicate key error
  if (err.code === 11000) {
    const field = Object.keys(err.keyValue)[0];
    const value = err.keyValue[field];
    const message = `${field} '${value}' already exists`;
    error = new ConflictError(message);
  }

  // Mongoose validation error
  if (err.name === 'ValidationError') {
    const errors = Object.values(err.errors).map(e => ({
      field: e.path,
      message: e.message,
      value: e.value
    }));
    const message = 'Validation failed';
    error = new ValidationError(errors, message);
  }

  // Express validator error
  if (err instanceof ValidationError) {
    error = new ValidationError(err.array(), err.message);
  }

  // JWT errors
  if (err.name === 'JsonWebTokenError') {
    const message = 'Invalid token';
    error = new AuthenticationError(message);
  }

  if (err.name === 'TokenExpiredError') {
    const message = 'Token expired';
    error = new AuthenticationError(message);
  }

  // Cloudinary errors
  if (err.name === 'CloudinaryError') {
    const message = 'Image upload failed';
    error = new AppError(message, 400);
  }

  // Multer errors
  if (err.code === 'LIMIT_FILE_SIZE') {
    const message = 'File too large';
    error = new AppError(message, 400);
  }

  if (err.code === 'LIMIT_UNEXPECTED_FILE') {
    const message = 'Unexpected file field';
    error = new AppError(message, 400);
  }

  // Rate limiting errors
  if (err.statusCode === 429) {
    error = new RateLimitError(err.message);
  }

  // Send error response
  const errorResponse = {
    success: false,
    error: {
      message: error.message,
      statusCode: error.statusCode,
      status: error.status
    }
  };

  // Add additional details in development
  if (process.env.NODE_ENV === 'development') {
    errorResponse.error.stack = err.stack;
    errorResponse.error.details = err;
  }

  // Add validation errors if they exist
  if (error.errors) {
    errorResponse.error.details = error.errors;
  }

  // Log operational errors
  if (!error.isOperational) {
    console.error('💥 NON-OPERATIONAL ERROR:', err);
    
    // In production, don't leak error details for non-operational errors
    if (process.env.NODE_ENV === 'production') {
      errorResponse.error.message = 'Something went wrong';
      errorResponse.error.statusCode = 500;
    }
  }

  res.status(error.statusCode).json(errorResponse);
};

/**
 * Async error handler wrapper (eliminates try-catch blocks)
 */
const catchAsync = (fn) => {
  return (req, res, next) => {
    Promise.resolve(fn(req, res, next)).catch(next);
  };
};

/**
 * 404 Not Found Handler
 */
const notFound = (req, res, next) => {
  const error = new NotFoundError(`Route ${req.originalUrl} not found`);
  next(error);
};

/**
 * Error logger (for production logging services)
 */
const errorLogger = (err, req, res, next) => {
  // Here you can integrate with logging services like:
  // - Winston
  // - Sentry
  // - LogRocket
  // - etc.
  
  const logEntry = {
    timestamp: new Date().toISOString(),
    method: req.method,
    url: req.originalUrl,
    ip: req.ip,
    userAgent: req.get('User-Agent'),
    userId: req.user?.id || 'anonymous',
    error: {
      message: err.message,
      stack: err.stack,
      statusCode: err.statusCode,
      name: err.name
    }
  };

  // Log to console in development
  if (process.env.NODE_ENV === 'development') {
    console.error('📋 Error Log:', logEntry);
  }

  // In production, send to external logging service
  if (process.env.NODE_ENV === 'production' && !err.isOperational) {
    // Example: send to Sentry, LogRocket, etc.
    console.error('🚨 PRODUCTION ERROR:', logEntry);
  }

  next(err);
};

module.exports = {
  errorHandler,
  catchAsync,
  notFound,
  errorLogger,
  AppError,
  ValidationError: class extends AppError {
    constructor(errors, message = 'Validation failed') {
      super(message, 400);
      this.errors = errors;
    }
  },
  AuthenticationError,
  AuthorizationError,
  NotFoundError,
  ConflictError,
  RateLimitError
};