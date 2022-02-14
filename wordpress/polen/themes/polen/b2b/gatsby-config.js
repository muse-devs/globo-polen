module.exports = {
  siteMetadata: {
    title: `Polen B2B`,
    siteUrl: `https://polen.me/empresas`,
  },
  plugins: [
    "gatsby-plugin-sass",
    {
      resolve: `gatsby-plugin-google-fonts`,
      options: {
        fonts: [
          `Inter`,
          `source sans pro\:400,600,700`,
        ],
        display: "swap",
      },
    },
  ],
};
