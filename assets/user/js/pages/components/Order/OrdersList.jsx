import React from "react";
import PropTypes from 'prop-types';

import { Alert } from "@userComponents/Elements/Alert";

import { OrdersItem } from "@userPages/Order/OrdersItem";

export function OrdersList ({ data, highlight, onDelete }) {
    return <div className="list my-4">
        <div className="list-table bg-white rounded-md shadow">
            <div className="items items-orders">
                <div className="item item-header uppercase text-gray-600">
                    <div className="item-content">
                        <div className="item-infos">
                            <div className="col-1">Date</div>
                            <div className="col-2">Inscriptions</div>
                            <div className="col-3">Montant HT</div>
                            <div className="col-4">Statut</div>
                            <div className="col-5 actions" />
                        </div>
                    </div>
                </div>

                {data.length > 0
                    ? data.map((elem) => {
                        return <OrdersItem key={elem.id} elem={elem} highlight={highlight} onDelete={onDelete} />;
                    })
                    : <Alert color="gray">Aucune donnée enregistrée.</Alert>
                }
            </div>
        </div>
    </div>
}

OrdersList.propTypes = {
    data: PropTypes.array.isRequired,
    onDelete: PropTypes.func.isRequired,
}
