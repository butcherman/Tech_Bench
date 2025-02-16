/*
|-------------------------------------------------------------------------------
| Search customer to find the Primary Site
|-------------------------------------------------------------------------------
*/
export const findPrimarySite = (customer: customer): customerSite => {
    let primary = customer.customer_site.find(
        (site) => site.cust_site_id === customer.primary_site_id
    );

    return primary ?? customer.customer_site[0];
};

// export const sortCustSites = (siteList: customerSite[], primaryId: number) => {
//     return siteList.sort((x) => (x.cust_site_id === primaryId ? -1 : 1));
// };
