# Stage 1: Build the Angular application
# Use a Node.js image to install dependencies and build the app
FROM node:20-alpine AS build

# Set the working directory
WORKDIR /app

# Copy package.json and package-lock.json (or yarn.lock)
# This allows Docker to cache the dependency installation layer
COPY package.json package-lock.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application files
COPY . .

# Build the Angular application in production mode
# Replace 'your-app-name' with the actual name if needed, but often 'ng build' suffices
# '--output-path /app/dist' directs the output to a standard location
# For Angular 16+, 'ng build' defaults to an 'app-name/browser' structure,
# so we use a simple output path to ensure consistency.
RUN npm run build -- --output-path /app/dist

# Stage 2: Serve the application with Nginx
# Use a very small, static web server image for the final container
FROM nginx:alpine AS final

# Remove the default Nginx configuration
RUN rm /etc/nginx/conf.d/default.conf

# Copy a custom Nginx configuration file
# This assumes you have an 'nginx.conf' file in your project root
# tailored to serve single-page applications (SPAs) like Angular
# (e.g., handling routing by always serving index.html for unknown paths).
# If you don't have one, you might need a simple default config.
# COPY nginx.conf /etc/nginx/conf.d/

# Simple default Nginx configuration for Angular SPAs:
COPY --from=build /app/dist /usr/share/nginx/html

# Create a minimal Nginx config for SPAs
RUN echo 'server { \
    listen 80; \
    location / { \
        root /usr/share/nginx/html; \
        index index.html; \
        try_files $uri $uri/ /index.html; \
    } \
}' > /etc/nginx/conf.d/default.conf

# Expose port 80 (where Nginx serves the app)
EXPOSE 80

# Command to run Nginx (this is the default command for the Nginx image)
CMD ["nginx", "-g", "daemon off;"]