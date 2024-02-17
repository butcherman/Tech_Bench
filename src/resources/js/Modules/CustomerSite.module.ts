export const findPrimarySite = (customer: customer) => {
    return customer.customer_site.find(
        (cust) => cust.cust_id === customer.primary_site_id
    );
};

export const sortCustSites = (siteList: customerSite[], primaryId: number) => {
    return siteList.sort((x) => (x.cust_site_id === primaryId ? -1 : 1));
};
