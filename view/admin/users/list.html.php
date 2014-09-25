<?php

use Goteo\Library\Text;

// paginacion
require_once 'library/pagination/pagination.php';

$filters = $this['filters'];
$users = $this['users'];

// la ordenación por cantidad y proyectos hay que hacerla aqui
if ($filters['order'] == 'amount') {
    uasort($users,
        function ($a, $b) {
            if ($a->namount == $b->namount) return 0;
            return ($a->namount < $b->namount) ? 1 : -1;
            }
        );
}
if ($filters['order'] == 'projects') {
    uasort($users,
        function ($a, $b) {
            if ($a->nprojs == $b->nprojs) return 0;
            return ($a->nprojs < $b->nprojs) ? 1 : -1;
            }
        );
}

$the_filters = '';
foreach ($filters as $key=>$value) {
    $the_filters .= "&{$key}={$value}";
}

$pagedResults = new \Paginated($users, 20, isset($_GET['page']) ? $_GET['page'] : 1);
?>
<a href="/admin/users/add" class="button">Create User</a>

<div class="widget board">
    <form id="filter-form" action="/admin/users" method="get">
        <table>
            <tr>
                <td>
                    <label for="role-filter">With role:</label><br />
                    <select id="role-filter" name="role" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Any role</option>
                    <?php foreach ($this['roles'] as $roleId=>$roleName) : ?>
                        <option value="<?php echo $roleId; ?>"<?php if ($filters['role'] == $roleId) echo ' selected="selected"';?>><?php echo $roleName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="interest-filter">Show users interested in:</label><br />
                    <select id="interest-filter" name="interest" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Any interest</option>
                    <?php foreach ($this['interests'] as $interestId=>$interestName) : ?>
                        <option value="<?php echo $interestId; ?>"<?php if ($filters['interest'] == $interestId) echo ' selected="selected"';?>><?php echo $interestName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="role-filter">Show users with role:</label><br />
                    <select id="role-filter" name="role" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Any role</option>
                    <?php foreach ($this['roles'] as $roleId=>$roleName) : ?>
                        <option value="<?php echo $roleId; ?>"<?php if ($filters['role'] == $roleId) echo ' selected="selected"';?>><?php echo $roleName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td colspan="2">
                    <label for="name-filter">By name or email:</label><br />
                    <input id="name-filter" name="name" value="<?php echo $filters['name']; ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="filter" value="Search">
                </td>
                <td>
                    <label for="order-filter">View by:</label><br />
                    <select id="order-filter" name="order" onchange="document.getElementById('filter-form').submit();">
                    <?php foreach ($this['orders'] as $orderId=>$orderName) : ?>
                        <option value="<?php echo $orderId; ?>"<?php if ($filters['order'] == $orderId) echo ' selected="selected"';?>><?php echo $orderName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>

    </form>
    <br clear="both" />
    <a href="/admin/users/?reset=filters">Remove filters</a>
</div>

<div class="widget board">
<?php if ($filters['filtered'] != 'yes') : ?>
    <p>You need to put some filters, there are too many records!</p>
<?php elseif (!empty($users)) : ?>
    <table>
        <thead>
            <tr>
                <th>Alias</th> <!-- view profile -->
                <th>User</th>
                <th>Email</th>
                <th>Projects</th>
                <th>Amount</th>
                <th>High</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($user = $pagedResults->fetchPagedRow()) :
                $adminNode = ($user->admin) ? $user->admin_node : null;
                ?>
            <tr>
                <td><a href="/user/profile/<?php echo $user->id; ?>" target="_blank" <?php echo ($adminNode != 'goteo') ? 'style="color: green;" title="Admin node '.$adminNode.'"' : 'title="View Public Profile"'; ?>><?php echo substr($user->name, 0, 20); ?></a></td>
                <td><strong><?php echo substr($user->id, 0, 20); ?></strong></td>
                <td><a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></td>
                <td><?php echo $user->nprojs; ?></td>
                <td><?php echo \amount_format($user->namount); ?> &euro;</td>
                <td><?php echo $user->register_date; ?></td>
            </tr>
            <tr>
                <td><a href="/admin/users/manage/<?php echo $user->id; ?>" title="Manage">[Manage]</a></td>
                <td><?php if ($user->nprojs > 0) {
                    if (!isset($_SESSION['admin_node']) || (isset($_SESSION['admin_node']) && $user->node == $_SESSION['admin_node'])) : ?>
                <a href="/admin/accounts/?name=<?php echo $user->email; ?>" title="See their contributions">[Contributions]</a>
                <?php else:  ?>
                <a href="/admin/invests/?name=<?php echo $user->email; ?>" title="See their contributions">[Contributions]</a>
                <?php endif; } ?></td>
                <td colspan="5" style="color:blue;">
                    <?php echo (!$user->active && $user->hide) ? ' Drop ' : ''; ?>
                    <?php echo $user->active ? '' : ' Inactive '; ?>
                    <?php echo $user->hide ? ' Hidden ' : ''; ?>
                    <?php echo $user->checker ? ' Revisor ' : ''; ?>
                    <?php echo $user->translator ? ' Translator ' : ''; ?>
                    <?php echo $user->caller ? ' Convener ' : ''; ?>
                    <?php echo $user->admin ? ' Admin ' : ''; ?>
                    <?php echo $user->manager ? ' Manager ' : ''; ?>
                    <?php echo $user->vip ? ' VIP ' : ''; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6"><hr /></td>
            </tr>
            <?php endwhile; ?>
        </tbody>

    </table>
</div>
<ul id="pagination">
<?php   $pagedResults->setLayout(new DoubleBarLayout());
        echo $pagedResults->fetchPagedNavigation($the_filters); ?>
</ul>
<?php else : ?>
<p>No records found</p>
<?php endif; ?>
